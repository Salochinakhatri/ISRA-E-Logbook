<?php

declare(strict_types=1);

/**
 * UROGYNAECOLOGY competency tree.
 * Cleaned of program tags (IMM / FCPS).
 *
 * @return list<array{id:int,label:string,children:list<array{id:int,label:string}>}>
 */
return [
    [
        'id'       => 401,
        'label'    => 'Urogynaecology - Clinical Assessment (OPD & Ward)',
        'children' => [
            ['id' => 4001, 'label' => 'Eliciting Pertinent history (Urinary symptoms, pelvic floor)'],
            ['id' => 4002, 'label' => 'Performing physical examination (pelvic floor, POP-Q)'],
            ['id' => 4003, 'label' => 'Requesting appropriate investigations'],
            ['id' => 4004, 'label' => 'Interpreting the results of investigations'],
            ['id' => 4005, 'label' => 'Deciding and implementing appropriate treatment'],
            ['id' => 4006, 'label' => 'Managing immediate complications'],
            ['id' => 4007, 'label' => 'Maintaining follow-up'],
            ['id' => 4008, 'label' => 'Completing bladder diary / frequency volume chart'],
            ['id' => 4009, 'label' => 'Pad test (1-hour / 24-hour)'],
            ['id' => 4010, 'label' => 'Urine analysis and Midstream Urine culture'],
        ],
    ],
    [
        'id'       => 402,
        'label'    => 'Urinary Incontinence Assessment & Management',
        'children' => [
            ['id' => 4020, 'label' => 'Stress Urinary Incontinence (SUI) – Assessment'],
            ['id' => 4021, 'label' => 'Urgency Urinary Incontinence (UUI) – Assessment'],
            ['id' => 4022, 'label' => 'Mixed Urinary Incontinence – Management'],
            ['id' => 4023, 'label' => 'Conservative management – pelvic floor muscle training (PFMT)'],
            ['id' => 4024, 'label' => 'Bladder retraining'],
            ['id' => 4025, 'label' => 'Pharmacological management of OAB / UUI'],
            ['id' => 4026, 'label' => 'Voiding dysfunction and urinary retention management'],
        ],
    ],
    [
        'id'       => 403,
        'label'    => 'Pelvic Organ Prolapse (POP) – Assessment & Conservative Mx',
        'children' => [
            ['id' => 4030, 'label' => 'POP-Q staging and documentation'],
            ['id' => 4031, 'label' => 'Anterior compartment prolapse (cystocele) assessment'],
            ['id' => 4032, 'label' => 'Posterior compartment prolapse (rectocele/enterocele) assessment'],
            ['id' => 4033, 'label' => 'Apical prolapse (uterine / vault prolapse) assessment'],
            ['id' => 4034, 'label' => 'Ring pessary insertion and follow-up'],
            ['id' => 4035, 'label' => 'Conservative management – pelvic floor physiotherapy'],
        ],
    ],
    [
        'id'       => 404,
        'label'    => 'Urodynamics',
        'children' => [
            ['id' => 4040, 'label' => 'Uroflowmetry and post-void residual (PVR) measurement'],
            ['id' => 4041, 'label' => 'Cystometry – filling and voiding phases'],
            ['id' => 4042, 'label' => 'Pressure-flow studies'],
            ['id' => 4043, 'label' => 'Urethral pressure profilometry'],
            ['id' => 4044, 'label' => 'Ambulatory urodynamics'],
            ['id' => 4045, 'label' => 'Videourodynamics'],
        ],
    ],
    [
        'id'       => 405,
        'label'    => 'Fistulae – Assessment & Initial Management',
        'children' => [
            ['id' => 4050, 'label' => 'Vesicovaginal fistula (VVF) – diagnosis and workup'],
            ['id' => 4051, 'label' => 'Ureterovaginal fistula – diagnosis'],
            ['id' => 4052, 'label' => 'Rectovaginal fistula – assessment'],
            ['id' => 4053, 'label' => 'Post-obstetric fistula – classification and initial care'],
            ['id' => 4054, 'label' => 'Methylene blue / dye test for fistula identification'],
        ],
    ],
    [
        'id'       => 406,
        'label'    => 'Defaecatory Dysfunction & Anorectal Disorders',
        'children' => [
            ['id' => 4060, 'label' => 'Faecal incontinence – history, examination, investigations'],
            ['id' => 4061, 'label' => 'Constipation and obstructed defaecation – management'],
            ['id' => 4062, 'label' => 'Obstetric Anal Sphincter Injuries (OASIS) – recognition & repair'],
            ['id' => 4063, 'label' => 'Third and fourth degree perineal tear management'],
            ['id' => 4064, 'label' => 'Endoanal / translabial ultrasound interpretation'],
        ],
    ],
    [
        'id'       => 407,
        'label'    => 'Urogynaecology – Operative Skills (General)',
        'children' => [
            ['id' => 4070, 'label' => 'Eliciting Pertinent history (advanced / complex)'],
            ['id' => 4071, 'label' => 'Performing Physical examination (advanced)'],
            ['id' => 4072, 'label' => 'Requesting appropriate investigations'],
            ['id' => 4073, 'label' => 'Interpreting results of investigations (urodynamics, imaging)'],
            ['id' => 4074, 'label' => 'Deciding and implementing appropriate treatment plan'],
            ['id' => 4075, 'label' => 'Managing complications'],
            ['id' => 4076, 'label' => 'Maintaining follow-up (post-operative)'],
        ],
    ],
    [
        'id'       => 408,
        'label'    => 'Incontinence Surgery',
        'children' => [
            ['id' => 4080, 'label' => 'Mid-urethral sling – TVT (Tension-free Vaginal Tape)'],
            ['id' => 4081, 'label' => 'Mid-urethral sling – TOT (Trans-Obturator Tape)'],
            ['id' => 4082, 'label' => 'Colposuspension (Burch) – open / laparoscopic'],
            ['id' => 4083, 'label' => 'Urethral bulking agents injection'],
            ['id' => 4084, 'label' => 'Botulinum toxin A injection into bladder'],
            ['id' => 4085, 'label' => 'Sacral nerve stimulation (SNS) implant'],
            ['id' => 4086, 'label' => 'Percutaneous tibial nerve stimulation (PTNS)'],
        ],
    ],
    [
        'id'       => 409,
        'label'    => 'Pelvic Organ Prolapse Surgery',
        'children' => [
            ['id' => 4090, 'label' => 'Anterior colporrhaphy (cystocele repair)'],
            ['id' => 4091, 'label' => 'Posterior colporrhaphy (rectocele repair)'],
            ['id' => 4092, 'label' => 'Vaginal hysterectomy with pelvic floor repair'],
            ['id' => 4093, 'label' => 'Sacrospinous ligament fixation'],
            ['id' => 4094, 'label' => 'Sacrocolpopexy – abdominal / laparoscopic'],
            ['id' => 4095, 'label' => 'Iliococcygeus fixation / uterosacral suspension'],
            ['id' => 4096, 'label' => 'Manchester (Fothergill) repair'],
            ['id' => 4097, 'label' => 'Le Fort colpocleisis (obliterative procedure)'],
        ],
    ],
    [
        'id'       => 410,
        'label'    => 'Fistula Surgery',
        'children' => [
            ['id' => 4100, 'label' => 'VVF repair – vaginal route'],
            ['id' => 4101, 'label' => 'VVF repair – abdominal (transperitoneal) route'],
            ['id' => 4102, 'label' => 'VVF repair – laparoscopic / robotic-assisted'],
            ['id' => 4103, 'label' => 'Ureterovaginal fistula repair / re-implantation'],
            ['id' => 4104, 'label' => 'Rectovaginal fistula repair'],
            ['id' => 4105, 'label' => 'Urethrovaginal fistula repair'],
        ],
    ],
    [
        'id'       => 411,
        'label'    => 'Cystoscopy & Endoscopic Procedures',
        'children' => [
            ['id' => 4110, 'label' => 'Cystoscopy – diagnostic'],
            ['id' => 4111, 'label' => 'Cystoscopy – operative (foreign body / biopsy)'],
            ['id' => 4112, 'label' => 'Ureteric stent (DJ stent) insertion / removal'],
            ['id' => 4113, 'label' => 'Hydrodistension of bladder (for IC/BPS)'],
        ],
    ],
    [
        'id'       => 412,
        'label'    => 'Anorectal & Pelvic Floor Reconstruction',
        'children' => [
            ['id' => 4120, 'label' => 'External anal sphincter repair (overlapping sphincteroplasty)'],
            ['id' => 4121, 'label' => 'Repair of 3rd / 4th degree perineal tear'],
            ['id' => 4122, 'label' => 'Rectocele repair (posterior compartment)'],
            ['id' => 4123, 'label' => 'Perineal body reconstruction'],
        ],
    ],
    [
        'id'       => 413,
        'label'    => 'Neurogenic Bladder & Special Situations',
        'children' => [
            ['id' => 4130, 'label' => 'Neurogenic bladder – assessment and management'],
            ['id' => 4131, 'label' => 'Clean intermittent self-catheterisation (CISC) teaching'],
            ['id' => 4132, 'label' => 'Suprapubic catheter insertion / management'],
            ['id' => 4133, 'label' => 'Management of postmenopausal GSM / urogenital atrophy'],
            ['id' => 4134, 'label' => 'Urogynaecology in neurological disease (MS, spina bifida)'],
        ],
    ],
];
