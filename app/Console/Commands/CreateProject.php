<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;

class CreateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyit:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->writeln('Project creation');
        $this->output->writeln('');
        $this->output->writeln('Current projects:');
        $projects = Project::all();
        foreach ($projects as $project) {
            $this->output->write('[' . $project->id . '] -> ');
            $this->output->writeln($project->name);
        }
        if ($this->confirm('Do you wish to continue?')) {
            $projectName = $this->ask('What is the project name?');
            if (Project::whereName($projectName)->first()) {
                $this->output->writeln('Project name already in use...');
                return;
            }
            $user = User::whereUsername('admin')->first();

            $project = Project::create([
                'user_id' => $user->id,
                'name' => $projectName
            ]);
            $this->output->writeln('Project created:');
            $this->output->write('[' . $project->id . '] -> ');
            $this->output->writeln($project->name);
            $this->output->writeln('');
        }
        return;
    }
}
