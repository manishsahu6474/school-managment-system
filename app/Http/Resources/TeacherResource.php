<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'teacher_name' => $this->user->name ?? 'N/A',
            'email' => $this->user->email ?? 'N/A',
            'phone' => $this->phone,
            'salary' => $this->salary,
            'qualification' => $this->qualification,
            'joining_date' => $this->joining_date ? date('d-M-Y', strtotime($this->joining_date)) : 'N/A',
            'subject_name' => $this->subjects->isEmpty()
                ? 'Not Assigned'
                : $this->subjects->pluck('subject_name'),
            'class_name'   => $this->classes->isEmpty()
                ? 'Not Assigned'
                : $this->classes->pluck('class_name'),
            'status_text' => $this->status == 1 ? 'Active' : ($this->status == 0 ? 'Pending' : 'Inactive'),
        ];
    }
}
