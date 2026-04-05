<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject_name' => $this->subject_name,
            'subject_teacher' => $this->teachers->isEmpty() 
            ? 'Not Assigned': $this->teachers->map(function ($teacher) {
                return [
                    'teacher_id'   => $teacher->id,
                    'teacher_name' => $teacher->user->name ?? 'N/A',
                    'class_name'   => $teacher->classes->isEmpty()
                        ? 'Not Assigned'
                        : $teacher->classes->pluck('class_name')->toArray()
                ];
            }),
        ];
    }
}
