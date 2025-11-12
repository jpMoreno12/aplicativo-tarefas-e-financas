<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Goal;
use App\Models\Task;
use App\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'monthly_income' => 5000.00,
        ]);

        // Create categories
        $workCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Trabalho',
        ]);

        $personalCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Pessoal',
        ]);

        $studyCategory = Category::create([
            'user_id' => $user->id,
            'name' => 'Estudos',
        ]);

        // Create goals
        $healthGoal = Goal::create([
            'user_id' => $user->id,
            'title' => 'Melhorar a Saúde',
            'description' => 'Adotar hábitos mais saudáveis e fazer exercícios regularmente.',
        ]);

        $careerGoal = Goal::create([
            'user_id' => $user->id,
            'title' => 'Crescimento Profissional',
            'description' => 'Aprender novas tecnologias e melhorar minhas habilidades profissionais.',
        ]);

        // Create tasks
        Task::create([
            'user_id' => $user->id,
            'category_id' => $workCategory->id,
            'goal_id' => $careerGoal->id,
            'title' => 'Estudar Laravel',
            'due_date' => now()->addDays(3),
            'reward' => 'Assistir um filme',
            'position' => 1,
        ]);

        Task::create([
            'user_id' => $user->id,
            'category_id' => $personalCategory->id,
            'goal_id' => $healthGoal->id,
            'title' => 'Fazer exercícios',
            'due_date' => now(),
            'reward' => 'Smoothie de frutas',
            'position' => 2,
        ]);

        Task::create([
            'user_id' => $user->id,
            'category_id' => $studyCategory->id,
            'title' => 'Ler 30 páginas de um livro',
            'due_date' => now()->addDay(),
            'position' => 3,
        ]);

        // Create a completed task
        Task::create([
            'user_id' => $user->id,
            'category_id' => $workCategory->id,
            'title' => 'Revisar código do projeto',
            'completed_at' => now()->subHours(2),
            'reward' => 'Café especial',
            'position' => 4,
        ]);

        // Create expenses
        Expense::create([
            'user_id' => $user->id,
            'name' => 'Supermercado',
            'amount' => 250.00,
            'category' => 'Alimentação',
            'date' => now()->subDays(2),
        ]);

        Expense::create([
            'user_id' => $user->id,
            'name' => 'Gasolina',
            'amount' => 120.00,
            'category' => 'Transporte',
            'date' => now()->subDays(5),
        ]);

        Expense::create([
            'user_id' => $user->id,
            'name' => 'Cinema',
            'amount' => 45.00,
            'category' => 'Lazer',
            'date' => now()->subWeek(),
        ]);

        Expense::create([
            'user_id' => $user->id,
            'name' => 'Conta de luz',
            'amount' => 180.00,
            'category' => 'Utilidades',
            'date' => now()->subDays(10),
        ]);
    }
}
