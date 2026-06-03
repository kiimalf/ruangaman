<?php

namespace Database\Seeders;

use App\Models\Hypothesis;
use App\Models\Question;
use App\Models\Rule;
use App\Models\RuleCondition;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        // Map hypothesis labels to IDs
        $h = Hypothesis::pluck('id', 'label');

        // Map question order (P1=first created, P2=second, etc.)
        $questions = Question::orderBy('id')->pluck('id')->toArray();

        // Helper: P-index to question ID (P1 = index 0, P2 = index 1, etc.)
        $p = fn(int $premisNumber) => $questions[$premisNumber - 1];

        // Define all rules with their conditions
        // Format: [hypothesis_label, certainty_factor, [premise_numbers]]
        $rules = [
            // Rule 1 — Pelecehan Seksual Nonfisik: P1 + P2 + P3 → H1
            ['Pelecehan Seksual Nonfisik', 1.00, [1, 2, 3]],

            // Rule 2 — Pelecehan Seksual Fisik: P1 + P4 + P5 → H2
            ['Pelecehan Seksual Fisik', 1.00, [1, 4, 5]],

            // Rule 3 — Pemaksaan Kontrasepsi: P1 + P6 → H3
            ['Pemaksaan Kontrasepsi', 1.00, [1, 6]],

            // Rule 4 — Pemaksaan Sterilisasi: P1 + P7 → H4
            ['Pemaksaan Sterilisasi', 1.00, [1, 7]],

            // Rule 5 — Pemaksaan Perkawinan: P1 + P8 → H5
            ['Pemaksaan Perkawinan', 1.00, [1, 8]],

            // Rule 6 — Penyiksaan Seksual: P1 + P9 + P10 → H6
            ['Penyiksaan Seksual', 1.00, [1, 9, 10]],

            // Rule 7 — Eksploitasi Seksual: P1 + P11 → H7
            ['Eksploitasi Seksual', 1.00, [1, 11]],

            // Rule 8 — Perbudakan Seksual: P1 + P12 + P13 → H8
            ['Perbudakan Seksual', 1.00, [1, 12, 13]],

            // Rule 9A — KSBE (Perekaman): P1 + P14 → H9
            ['Kekerasan Seksual Berbasis Elektronik (KSBE)', 1.00, [1, 14]],

            // Rule 9B — KSBE (Penyebaran): P1 + P15 → H9
            ['Kekerasan Seksual Berbasis Elektronik (KSBE)', 1.00, [1, 15]],

            // Rule 9C — KSBE (Penguntitan): P1 + P16 → H9
            ['Kekerasan Seksual Berbasis Elektronik (KSBE)', 1.00, [1, 16]],

            // Rule 10A — KSBE + Pemberatan (Perekaman + Ancaman): P1 + P14 + P17 → H9
            ['Kekerasan Seksual Berbasis Elektronik (KSBE)', 1.00, [1, 14, 17]],

            // Rule 10B — KSBE + Pemberatan (Penyebaran + Ancaman): P1 + P15 + P17 → H9
            ['Kekerasan Seksual Berbasis Elektronik (KSBE)', 1.00, [1, 15, 17]],
        ];

        foreach ($rules as [$hypothesisLabel, $cf, $premisNumbers]) {
            $rule = Rule::create([
                'hypothesis_id' => $h[$hypothesisLabel],
                'certainty_factor' => $cf,
            ]);

            foreach ($premisNumbers as $premisNumber) {
                RuleCondition::create([
                    'rule_id' => $rule->id,
                    'question_id' => $p($premisNumber),
                    'expected_answer' => 'YA',
                ]);
            }
        }
    }
}
