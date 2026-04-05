<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'student_name' => $this->user->name ?? 'N/A',
            'father_name' => $this->father_name,
            'email'        => $this->user->email ?? 'N/A',
            'roll_no'     => $this->roll_no ?? 'Not Assigned',
            'phone'       => $this->phone,
            'dob'         => $this->dob ? date('d-m-Y', strtotime($this->dob)) : null,
            'class_name'  => $this->classes->class_name ?? 'N/A',
            'class_id'    => $this->class_id,
            'status_code' => (int)$this->status,
            'status_text' => $this->getStatusLabel(),
            'joined_at' => $this->created_at ? $this->created_at->format('d M, Y') : 'N/A',
        ];
    }
    private function getStatusLabel()
    {
        return match ((int)$this->status) {
            0 => 'Pending',
            1 => 'Active',
            2 => 'Inactive',
            default => 'Unknown',
        };
    }
}
