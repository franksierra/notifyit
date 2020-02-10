<?php

namespace App\Console\Commands;

use App\Models\Credential;
use App\Models\Project;
use Illuminate\Console\Command;

class CreateCredential extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyit:credential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new credential for a project';

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
        $this->output->writeln('Credential creation');
        $this->output->writeln('');
        $this->output->writeln('Projects:');
        $projects = [];
        $projectsIndex = [];
        foreach (Project::all() as $project) {
            $projects[] = $project->name;
            $projectsIndex[$project->name] = $project->id;
        }
        $projectName = $this->choice('Choose the project you wish to create a credential for', $projects, null);
        $projectId = $projectsIndex[$projectName];
        $this->output->writeln('Creating a credential for:');
        $this->output->write('[' . $projectId . '] -> ');
        $this->output->writeln($projectName);
        $this->output->writeln('Current credentials:');

        $credentials = Credential::whereProjectId($projectId)->get([
            'id', 'production', 'prefix', 'prefix_value', 'api_key'
        ]);
        if ($credentials) {
            $headers = ['ID', 'isProduction', 'isPrefixed', 'Prefix', 'KEY'];
            $this->table($headers, $credentials->toArray());
        }
        if ($this->confirm('Do you wish to continue?')) {
            $isProduction = $this->confirm('Is this credential for Production?');
            $isPrefixed = $this->confirm('Does this credential have a Prefix?');
            if ($isPrefixed) {
                $prefix = $this->ask('What should the prefix say?');
            }
            $credential = Credential::create([
                'project_id' => $projectId,
                'production' => $isProduction,
                'prefix' => $isPrefixed,
                'prefix_value' => $prefix ?? ''
            ]);
            $this->output->writeln('Credential created:');
            $this->output->write('App Key -> ');
            $this->output->writeln($credential->api_key);
            $this->output->writeln('');

        }

        return;
    }
}
