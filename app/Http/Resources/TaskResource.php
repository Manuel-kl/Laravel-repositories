<?php

namespace App\Http\Resources;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->status_id,
            'status' => $this->status->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }

    public function with($request): array
    {
        return [
            'status' => 200,
        ];
    }
}
