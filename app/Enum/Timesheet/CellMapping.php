<?php

namespace App\Enum\Timesheet;

enum CellMapping: string
{
    case Name = 'B6';
    case Position = 'C6';
    case MonthYear = 'P6';
    case Supervisor = 'Y6';
    case EndDate = 'J24';
    case SupervisorApproval = 'C24';
    case EmployeeSignature = 'AA24';
    case Remarks = 'K28';
}
