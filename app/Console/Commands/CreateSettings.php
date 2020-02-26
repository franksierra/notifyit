<?php

namespace App\Console\Commands;

use App\Models\Credential;
use App\Models\EmailNotificationSetting;
use App\Models\Project;
use App\Models\PushNotificationSetting;
use App\Models\SmsNotificationSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyit:setting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates settings for a credential';

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
        $this->output->writeln('Settings creation');
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
        $this->output->write('[' . $projectId . '] -> ');
        $this->output->writeln($projectName);

        $this->output->writeln('Current credentials:');
        $credentials = [];
        $credentialsIndex = [];
        foreach (Credential::whereProjectId($projectId)->get() as $credential) {
            $name = $credential->id . "-" . $credential->production . "-" . $credential->prefix . "-" . $credential->prefix_value;
            $credentials[] = $name;
            $credentialsIndex[$name] = $credential->id;
        }
        $credentialName = $this->choice('Choose the credential you wish to configure settings for', $credentials, null);
        $credentialId = $credentialsIndex[$credentialName];

        $emailSettings = EmailNotificationSetting::whereCredentialId($credentialId)->get(['type', 'driver']);
        $pushSettings = PushNotificationSetting::whereCredentialId($credentialId)->get(['type', 'driver']);
        $smsSettings = SmsNotificationSetting::whereCredentialId($credentialId)->get(['type', 'driver']);
        if ($emailSettings || $pushSettings || $smsSettings) {
            $this->output->writeln('Currently configured:');
            $headers = ['Type', 'Driver'];
            $this->table($headers,
                array_merge(
                    $emailSettings->toArray(),
                    $pushSettings->toArray(),
                    $smsSettings->toArray()
                )
            );
            $types = [];
            if (count($emailSettings)==0) {
                $types[] = "email";
            }
            if (count($pushSettings)==0) {
                $types[] = "push";
            }
            if (count($smsSettings)==0) {
                $types[] = "sms";
            }
            $type = $this->choice('Witch type do you wish to configure:', $types);
            $this->output->writeln('Creating a ' . Str::upper($type) . ' driver');
            $config = [];
            $driver = '';
            switch ($type) {
                case 'push':
                    $driver = 'fcm';
                    $config = [
                        'endpoint' => 'https://fcm.googleapis.com/fcm/send',
                        'api_key' => $this->ask('What is the Google ApiKey?')
                    ];
                    break;
                case 'email':
                    $driver = $this->choice(
                        'Select the Driver you wish to use',
                        ['null', 'smtp'],
                        'null'
                    );
                    switch ($driver) {
                        case 'smtp':
                            $config = [
                                "host" => $this->ask('What is the Server?','smtp.mandrillapp.com'),
                                "port" => $this->ask('Witch port?', 587),
                                "encryption" => Str::upper($this->choice(
                                    'Witch encryption do you wish to use:',
                                    ['TLS', 'STARTTLS'],
                                    'TLS'
                                )),
                                "username" => $this->ask('Username?', 'maildelivery@ec.geainternacional.com'),
                                "password" => $this->ask('Password?'),
                                "mail_type" => "HTML"
                            ];
                            break;
                        default:
                            break;
                    }
                    break;
                case 'sms':
                    $driver = $this->choice(
                        'Select the Driver you wish to use',
                        ['null', 'eclipsoft', 'notificame'],
                        'null'
                    );
                    switch ($driver) {
                        case 'eclipsoft':
                            $config = [
                                'endpoint' => 'https://app2.eclipsoft.com:9443/wsSMSEmpresarial/wscSMSEmp.asmx?WSDL',
                                "service" => "CONTACTOSMS",
                                "emitter" => "GEANOTIFICACION",
                                "login" => "admin",
                                "pwd" => "gntf@csms",
                                "reference" => "1337",
                                "pc_name" => "PCNEW"
                            ];
                            break;
                        case 'notificame':
                            $config = [
                                'endpoint' => $this->choice('Endpoint?', [
                                    'https://notificame.claro.com.gt/',
                                    'https://notificame.claro.com.hn/',
                                    'https://notificame.claro.com.sv/',
                                    'https://notificame.claro.com.ni/',
                                    'https://notificame.claro.cr/',
                                ]),
                                'prefix' => $this->choice('Country code prefix', [
                                    '502',
                                    '503',
                                    '504',
                                    '505',
                                    '506',
                                ]),
                                'api_key' => $this->ask('What is the ApiKey?', ''),
                            ];
                            break;
                        default:
                            break;
                    }
                    break;
            }
            $this->output->writeln("An " . Str::upper($type) . " setting with $driver driver would be created:");
            $headers = ['Key', 'Value'];
            $table_config = [];
            foreach ($config as $k => $v) {
                $table_config[] = [$k, $v];
            }
            $this->table($headers, $table_config);
            if ($this->confirm('Do you wish to continue?')) {
                switch ($type) {
                    case 'push':
                        PushNotificationSetting::create([
                            'credential_id' => $credentialId,
                            'driver' => $driver,
                            'config' => $config
                        ]);
                        break;
                    case 'email':
                        EmailNotificationSetting::create([
                            'credential_id' => $credentialId,
                            'driver' => $driver,
                            'config' => $config
                        ]);
                        break;
                    case 'sms':
                        SmsNotificationSetting::create([
                            'credential_id' => $credentialId,
                            'driver' => $driver,
                            'config' => $config
                        ]);
                        break;
                }
                Artisan::call("horizon:terminate --wait");
            }
        }

        return;
    }
}
