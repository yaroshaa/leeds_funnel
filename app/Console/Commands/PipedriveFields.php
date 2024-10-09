<?php

namespace App\Console\Commands;

use App\Models\Field;
use App\Models\Option;
use App\Services\Pipedrive;
use Illuminate\Console\Command;

class PipedriveFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipedrive:fields {--id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var Pipedrive
     */
    private Pipedrive $pipedrive;

    /**
     * Create a new command instance.
     *
     * @param Pipedrive $pipedrive
     */
    public function __construct(Pipedrive $pipedrive)
    {
        parent::__construct();

        $this->pipedrive = $pipedrive;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        foreach (['dealFields', 'personFields'] as $fieldName) {

            $fields = $this->pipedrive->{$fieldName}(['limit' => 500])->get()->data;

            foreach ($fields as $field) {
                if ($field && $field->id) {

                    /** @var Field $created */
                    $savedField = Field::updateOrCreate([
                        'pipedrive_id' => $field->id,
                    ], [
                        'name' => $field->name,
                        'key' => $field->key,
                        'type' => $field->field_type,
                        'appointment' => $fieldName,
                    ]);

                    if (isset($field->options)) {
                        Option::upsert(
                            collect($field->options)->map(fn($option) => [
                                'field_id' => $savedField->id,
                                'pipedrive_id' => $option->id,
                                'label' => $option->label,
                            ])->toArray(),
                            ['pipedrive_id', 'field_id'],
                            ['label']
                        );
                    }
                }
            }
        }

        $this->info('Fields updated successfully.');

        return self::SUCCESS;
    }
}
