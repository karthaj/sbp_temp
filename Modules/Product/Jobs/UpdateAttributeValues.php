<?php

namespace Modules\Product\Jobs;

use Modules\Product\Entities\Attribute;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class UpdateAttributeValues implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    public attribute;
    public values;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attribute $attribute, $values)
    {
        $this->attribute = $attribute;
        $this->values = $values;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->attribute->values->each(function ($value, $index) {
            $value->timestamps = false;
            $value->update(array_only($this->values[$index], ['name', 'sort_order']));
        });
    }
}
