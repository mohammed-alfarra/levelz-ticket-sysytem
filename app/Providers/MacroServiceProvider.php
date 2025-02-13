<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        HasMany::macro('syncRelation', function ($relation, $newValues): array {
            $oldValues = $this->parent->{$relation}()
                ->get()
                ->keyBy('id');

            $results = [
                'attached' => [],
                'detached' => [],
                'updated' => [],
            ];

            foreach ($newValues as $value) {
                if ($value['id'] && $date = $oldValues->get($value['id'])) {
                    $date->fill($value);
                    $date->save();
                    if ($date->getChanges()) {
                        $results['updated'][] = $date;
                    }

                    $oldValues->forget($value['id']);
                } else {
                    $results['attached'][] = $this->parent->{$relation}()->create($value);
                }
            }

            foreach ($oldValues as $deleted) {
                $results['detached'][] = $deleted->delete();
            }

            return $results;
        });
    }
}
