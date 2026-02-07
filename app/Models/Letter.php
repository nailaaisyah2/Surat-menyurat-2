<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Letter extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'pengirim_id',
        'penerima_division_id',
        'penerima_user_id',
        'jenis',
        'judul',
        'isi',
        'tanggal_pertemuan',
        'jam_pertemuan',
        'status',
        'catatan_petugas',
        'responded_by',
        'responded_at',
        'lampiran',
        'lampiran_response',
    ];

    protected $casts = [
        'tanggal_pertemuan' => 'date',
        'responded_at' => 'datetime',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerimaDivision()
    {
        return $this->belongsTo(Division::class, 'penerima_division_id');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function penerimaUser()
    {
        return $this->belongsTo(User::class, 'penerima_user_id');
    }

    protected function lampiran(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeLampiranValue($value),
            set: fn ($value) => $this->prepareLampiranForStorage($value),
        );
    }

    protected function lampiranResponse(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeLampiranValue($value),
            set: fn ($value) => $this->prepareLampiranForStorage($value),
        );
    }

    /**
     * @return array<int, string>
     */
    protected function normalizeLampiranValue($value): array
    {
        if (blank($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return [$value];
    }

    protected function prepareLampiranForStorage($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        $value = array_values(array_filter($value));

        return empty($value) ? null : json_encode($value);
    }
}

