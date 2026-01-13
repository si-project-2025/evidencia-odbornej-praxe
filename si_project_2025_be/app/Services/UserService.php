<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserService
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            User::where('email', $data['email'])
                ->whereNull('email_verified_at')
                ->delete();

            DB::table('pending_registrations')
                ->where('email', $data['email'])
                ->delete();

            $addressId = null;

            if ($data['role'] === 'student' && isset($data['address'])) {
                $addr = $data['address'];
                $address = Address::create([
                    'street' => $addr['street'],
                    'house_number' => $addr['house_number'],
                    'city' => $addr['city'],
                    'zip_code' => $addr['zip_code'],
                    'country' => $addr['country'],
                ]);
                $addressId = $address->address_id;
            }

            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(40)),
                'role_id' => Role::where('name', $data['role'])->firstOrFail()->role_id,
                'address_id' => $addressId,
                'alt_email' => $data['alt_email'] ?? null,
                'study_program' => $data['study_program'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'created_at' => Carbon::now(),
            ]);

            $token = $this->createPasswordResetToken($user);
            $this->sendSetPasswordEmail($user, $token);

            return $user;
        });
    }

    public function registerCompanyEmail(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roleId = Role::where('name', 'firma')->firstOrFail()->role_id;


            $user = User::create([
                'name' => 'Pending',
                'surname' => 'Company',
                'email' => $data['email'],
                'password' => Hash::make(Str::random(40)),
                'role_id' => $roleId,
                'created_at' => Carbon::now(),
            ]);

            $token = $this->createPasswordResetToken($user);
            $this->sendSetCompanyPasswordEmail($user, $token);

            return $user;
        });
    }

    public function completeCompanyRegistration(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (isset($data['company_id']) && $data['company_id']) {
                $company = Company::findOrFail($data['company_id']);

                $company->update(['user_id' => $user->users_id]);

                $user->update([
                    'name' => $company->name,
                    'surname' => 'Firma',
                ]);
            } else {

                $addr = $data['address'];
                $address = Address::create([
                    'street' => $addr['street'],
                    'house_number' => $addr['house_number'],
                    'city' => $addr['city'],
                    'zip_code' => $addr['zip_code'],
                    'country' => $addr['country'],
                ]);

                $company = Company::create([
                    'name' => $data['company_name'],
                    'ico' => $data['ico'],
                    'address_id' => $address->address_id,
                    'user_id' => $user->users_id,
                ]);


                $user->update([
                    'name' => $company->name,
                    'surname' => 'Firma',
                ]);
            }

            return $user->fresh();
        });
    }

    private function createPasswordResetToken(User $user): string
    {
        $token = Str::random(60);

        DB::table('pending_registrations')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'user_data' => json_encode([
                'name' => $user->name,
                'surname' => $user->surname,
                'role_id' => $user->role_id,
                'address_id' => $user->address_id,
                'alt_email' => $user->alt_email,
                'study_program' => $user->study_program,
                'phone_number' => $user->phone_number,
            ]),
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }

    private function sendSetPasswordEmail(User $user, string $token): void
    {
        $frontendUrl = config('app.frontend_url');
        $resetUrl = "{$frontendUrl}/set-password?token={$token}&email=" . urlencode($user->email);

        Mail::send('emails.set-initial-password', ['user' => $user, 'url' => $resetUrl], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Aktivujte si účet a nastavte heslo');
        });
    }

    private function sendSetCompanyPasswordEmail(User $user, string $token): void
    {
        $frontendUrl = config('app.frontend_url');
        $resetUrl = "{$frontendUrl}/set-password?token={$token}&email=" . urlencode($user->email) . "&type=company";

        Mail::send('emails.set-company-password', ['user' => $user, 'url' => $resetUrl], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Aktivujte si firemný účet a nastavte heslo');
        });
    }

    public function setPassword(array $data): void
    {
        $passwordReset = DB::table('pending_registrations')
            ->where('email', $data['email'])
            ->first();

        if (!$passwordReset) {
            throw new \Exception('Token nenájdený alebo už bol použitý.');
        }

        // Overenie tokenu
        if (!Hash::check($data['token'], $passwordReset->token)) {
            throw new \Exception('Neplatný token.');
        }

        // Kontrola expirácie (60 minút)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            throw new \Exception('Token expiroval. Požiadajte o nový aktivačný email.');
        }

        // Nastavenie hesla
        $user = User::where('email', $data['email'])->firstOrFail();
        $user->password = Hash::make($data['password']);
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Vymazanie tokenu
        DB::table('pending_registrations')->where('email', $data['email'])->delete();
    }
}
