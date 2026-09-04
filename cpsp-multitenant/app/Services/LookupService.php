<?php

declare(strict_types=1);

namespace App\Services;

class LookupService
{
    /**
     * Medical e-Logbook Form Types.
     *
     * @return array<string, string>
     */
    public static function formTypes(): array
    {
        return [
            '1'  => '(FORM - A)- Record Of Operations / Procedures',
            '2'  => '(FORM - B)- Record Of Emergency Procedures',
            '3'  => '(FORM - D)- Cases presented at clinico-pathological Conference',
            '6'  => '(FORM - F)- Miscellaneous',
            '7'  => '(FORM - G)- Record of autopsy/exhumation',
            '8'  => '(FORM - H)- Record of medico legal cases',
            '9'  => '(FORM - I)- Clinical Case Discussion',
            '10' => '(FORM - J)- Record of Orthodontic Procedure',
        ];
    }

    /**
     * Training / Procedure Levels.
     *
     * @return array<string, string>
     */
    public static function levels(): array
    {
        return [
            '1'    => 'Observer Status',
            '2'    => 'Assistant Status',
            '3'    => 'Performed under direct Supervision',
            '4'    => 'Performed under indirect supervision',
            '5'    => 'Performed independently',
            '5555' => 'Other',
        ];
    }

    /**
     * Patient Outcomes.
     *
     * @return array<string, string>
     */
    public static function outcomes(): array
    {
        return [
            '2'  => 'Admitted to inpatient facility',
            '3'  => 'Treated and called for follow-up',
            '4'  => 'Referred to other specialty unit',
            '5'  => 'Death of the patient',
            '7'  => 'Improved',
            '8'  => 'Discharged',
            '9'  => 'Treated',
            '10' => 'Under Treatment',
            '11' => 'Treatment Failure',
            '12' => 'Follow Up',
            '6'  => 'Other',
        ];
    }

    /**
     * Patient Genders.
     *
     * @return list<string>
     */
    public static function genders(): array
    {
        return ['Male', 'Female'];
    }

    /**
     * Age Units.
     *
     * @return list<string>
     */
    public static function ageUnits(): array
    {
        return ['Year[s]', 'Month[s]', 'Week[s]', 'Day[s]'];
    }

    /**
     * Get label for a specific form type ID.
     */
    public static function formTypeLabel(string|int|null $id): string
    {
        return self::formTypes()[(string) $id] ?? (string) $id;
    }

    /**
     * Get label for a specific level ID.
     */
    public static function levelLabel(string|int|null $id): string
    {
        return self::levels()[(string) $id] ?? (string) $id;
    }

    /**
     * Get label for a specific outcome ID.
     */
    public static function outcomeLabel(string|int|null $id): string
    {
        return self::outcomes()[(string) $id] ?? (string) $id;
    }
}
