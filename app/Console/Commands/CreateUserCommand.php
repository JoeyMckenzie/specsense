<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Create a new user interactively';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Welcome to the user creation wizard!');
        $this->newLine();

        $firstName = text('What is the user\'s first name?', required: true);
        $lastName = text('What is the user\'s last name?', required: true);

        $email = text(
            'What is the user\'s email?',
            required: true,
            validate: fn (string $value): ?string => User::whereEmail($value)->exists() // @phpstan-ignore-line
                ? 'A user with this email already exists.'
                : null
        );

        $password = password(
            'What is the user\'s password?',
            required: true,
            validate: fn (string $value): ?string => strlen($value) < 8
                ? 'The password must be at least 8 characters.'
                : null,
        );

        password(
            'Please confirm the password',
            required: true,
            validate: fn (string $value): ?string => $value !== $password
                ? 'The passwords do not match.'
                : null,
        );

        if (! confirm('Are you sure you want to create this user?')) {
            $this->error('User creation cancelled.');

            return self::FAILURE;
        }

        try {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->info('User created successfully!');
            $this->table(
                ['ID', 'First Name', 'Last Name', 'Email'],
                [[$user->id, $user->first_name, $user->last_name, $user->email]]
            );

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('Failed to create user: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
