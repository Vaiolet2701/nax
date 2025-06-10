<form id="rejectForm" action="{{ route('admin.enrollments.reject') }}" method="POST">
    @csrf
    <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
    
    <div class="form-group">
        <label for="reason">Причина отказа:</label>
        <textarea name="rejection_reason" id="reason" class="form-control" rows="5" required></textarea>
    </div>
    
    <div class="form-group text-right">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Отмена</button>
        <button type="submit" class="btn btn-danger">Отклонить заявку</button>
    </div>
</form>