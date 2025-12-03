<?php

namespace Database\Factories\Master;

use App\Enum\Master\EnglishSkill;
use App\Enum\Master\RiwayatPendidikan;
use App\Enum\Master\StatusKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name(),
            'posisi' => $this->faker->jobTitle(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail(),
            'jenis_kelamin' => $this->faker->randomElement(['laki-laki', 'perempuan']),
            'riwayat_pendidikan' => $this->faker->randomElement(RiwayatPendidikan::cases()),
            'institusi_pendidikan' => $this->faker->company(),
            'english_skill'=>$this->faker->randomElement(EnglishSkill::cases()),
            'pengalaman_kerja'=>$this->faker->numberBetween(1,10),
            'tanggal_bergabung'=>$this->faker->date(),
            'tanggal_expired'=>null,
            'masa_kerja'=>$this->faker->numberBetween(1,10),
            'status'=>StatusKerja::Tetap,
            'cv'=>$this->faker->filePath(),
            'ktp'=>$this->faker->filePath(),
            'kk'=>$this->faker->filePath(),
            'npwp'=>$this->faker->numerify('###.###.###-###.###'),
        ];
    }
}
