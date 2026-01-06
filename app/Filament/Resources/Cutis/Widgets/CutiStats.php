<?php

namespace App\Filament\Resources\Cutis\Widgets;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Permission;
use App\Models\Cuti;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CutiStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $all = $this->getValue('all');
        $approved = $this->getValue('approved');
        $rejected = $this->getValue('rejected');
        return [
            Stat::make($this->getLabel('all'), $all),
            Stat::make($this->getLabel('approved'), $approved),
            Stat::make($this->getLabel('rejected'), $rejected),
        ];
    }
    public function getLabel($type): string
    {
        $user = Auth::user();
        switch ($type) {
            case 'all':
                return "Semua Cuti";
            case 'approved':
                switch ($user) {
                    case $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI):
                        return "Perlu Dilihat";
                    case $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI):
                    case $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI):
                        return "Perlu Persetujuan";
                    default:
                        return "Disetujui";
                }
            case 'rejected':
                switch ($user) {
                    case $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI):
                        return 'Perlu diteruskan';
                    case $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI):
                    case $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI):
                        return 'Pending';
                    default:
                        return 'Ditolak';
                }
            default:
                return 0;
        }
    }
    public function getValue($type)
    {
        $user = Auth::user();
        switch ($type) {
            case 'all':
                switch ($user) {
                    case $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::Diajukan)
                            ->orWhere('status', StatusPengajuan::MenungguHR)
                            ->count();
                        return $value;
                    case $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI):
                    case $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::MenungguDirektur)
                            ->orWhere('status', StatusPengajuan::MenungguHR)
                            ->orWhere('status', StatusPengajuan::Diajukan)
                            ->count();
                        return $value;
                    default:
                        $query = Cuti::query();
                        $value = $query->where('karyawan_id',$user->karyawan_id)->count();
                        return $value;
                }
            case 'approved':
                switch ($user) {
                    case $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::Diajukan)
                            ->count();
                        return $value;
                    case $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI):
                    case $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::MenungguDirektur)
                            ->count();
                        return $value;
                    default:
                        $query = Cuti::query();
                        $value = $query->where('karyawan_id',$user->karyawan_id)
                            ->where('status', StatusPengajuan::Disetujui)
                            ->count();
                        return $value;
                }
            case 'rejected':
                switch ($user) {
                    case $user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::MenungguHR)
                            ->count();
                        return $value;
                    case $user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI):
                    case $user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI):
                        $query = Cuti::query();
                        $value = $query->where('status', StatusPengajuan::MenungguHR)
                            ->orWhere('status', StatusPengajuan::Diajukan)
                            ->count();
                        return $value;
                    default:
                        $query = Cuti::query();
                        $value = $query->where('karyawan_id',$user->karyawan_id)
                            ->where('status', StatusPengajuan::Ditolak)
                            ->count();
                        return $value;
                }
            default:
                return 0;
        }
    }
}
