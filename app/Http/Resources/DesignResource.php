<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $status = [
            'label' => 'Pendiente',
            'color' => 'text-amber-500',
            'icon' => '<i class="fa-regular fa-clock"></i>',
        ];

        // if ($this->quote_acepted === 1) {
        //     $status = [
        //         'label' => 'Autorizado',
        //         'color' => 'text-green-500',
        //         'icon' => '<i class="fa-solid fa-check"></i>',
        //     ];
        // } else if ($this->quote_acepted === 0) {
        //     $status = [
        //         'label' => 'Rechazado',
        //         'color' => 'text-red-500',
        //         'icon' => '<i class="fa-solid fa-xmark"></i>',
        //     ];
        // }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'plans_image' => $this->plans_image,
            'logo_image' => $this->logo_image,
            'company_branch_name' => $this->company_branch_name,
            'contact_name' => $this->contact_name,
            'dimensions' => $this->dimensions,
            'status' => $status,
            'design_data' => $this->design_data,
            'specifications' => $this->specifications,
            'pantones' => $this->pantones ?? 'N/A',
            'design_type' => $this->whenLoaded('designType'),
            'designer' => $this->whenLoaded('designer'),
            'user' => $this->whenLoaded('user'),
            'media' => $this->getMedia('results')->all(),
            'media_plano' => $this->getMedia('plano')->all(),
            'media_logo' => $this->getMedia('logo')->all(),
            'measure_unit' => $this->measure_unit,
            'authorized_user_name' => $this->authorized_user_name ?? '--',
            'authorized_at' => $this->authorized_at?->isoFormat('YYYY MMM DD, h:mm A') ?? 'No autorizado',
            'expected_end_at' => $this->expected_end_at?->isoFormat('DD MMM, YYYY') ?? '--',
            'original_design_id' => $this->original_design_id,
            'is_complex' => $this->is_complex,
            'reuse_percentage' => $this->reuse_percentage,
            'modifications' => $this->whenLoaded('modifications'),
            'design_modifications' => $this->design_modifications,
            'started_at' => $this->started_at?->isoFormat('YYYY MMM DD, h:mm A') ?? 'No iniciado',
            'finished_at' => $this->finished_at?->isoFormat('YYYY MMM DD'),
            'created_at' => $this->created_at?->isoFormat('DD MMM, YYYY h:mm A'),
            'updated_at' => $this->updated_at?->isoFormat('YYYY MMM DD'),
        ];
    }
}
