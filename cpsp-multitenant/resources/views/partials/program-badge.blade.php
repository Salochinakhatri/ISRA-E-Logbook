@if($program === 'ms')
    <div class="elog-program-badge">
        <span class="badge badge--urogyn"><i class="fa-solid fa-stethoscope"></i> MS (GYNAECOLOGY & OBSTETRICS)</span>
    </div>
@elseif($program === 'dgo')
    <div class="elog-program-badge">
        <span class="badge badge--obgyn"><i class="fa-solid fa-heart-pulse"></i> DGO (GYNAECOLOGY & OBSTETRICS)</span>
    </div>
@elseif($program === 'md')
    <div class="elog-program-badge">
        <span class="badge badge--urogyn"><i class="fa-solid fa-stethoscope"></i> MD (INTERNAL MEDICINE)</span>
    </div>
@elseif($program === 'imm')
    <div class="elog-program-badge">
        <span class="badge badge--obgyn"><i class="fa-solid fa-heart-pulse"></i> IMM (INTERNAL MEDICINE)</span>
    </div>
@elseif($program === 'urogyn')
    <div class="elog-program-badge">
        <span class="badge badge--urogyn"><i class="fa-solid fa-stethoscope"></i> UROGYNAECOLOGY</span>
    </div>
@elseif($program === 'obgyn')
    <div class="elog-program-badge">
        <span class="badge badge--obgyn"><i class="fa-solid fa-heart-pulse"></i> OBSTETRICS AND GYNAECOLOGY</span>
    </div>
@endif
