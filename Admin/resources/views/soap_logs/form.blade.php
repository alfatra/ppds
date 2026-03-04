@csrf

<div class="mb-3">
    <label for="patient_id" class="form-label">Patient ID</label>
    <input type="number" class="form-control" name="patient_id" value="{{ old('patient_id', $log->patient_id ?? '') }}" required>
</div>
<!-- doctor_id set automatically to logged-in user -->

<div class="mb-3">
    <label for="visit_date" class="form-label">Visit Date</label>
    <input type="datetime-local" class="form-control" name="visit_date" value="{{ old('visit_date', isset($log->visit_date) ? $log->visit_date->format('Y-m-d\TH:i') : '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Subjective</label>
    <textarea class="form-control" name="subjective" rows="3">{{ old('subjective', $log->subjective ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Objective</label>
    <textarea class="form-control" name="objective" rows="3">{{ old('objective', $log->objective ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Assessment</label>
    <textarea class="form-control" name="assessment" rows="3">{{ old('assessment', $log->assessment ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Plan</label>
    <textarea class="form-control" name="plan" rows="3">{{ old('plan', $log->plan ?? '') }}</textarea>
</div>
