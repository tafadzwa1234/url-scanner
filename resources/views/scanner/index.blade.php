@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Scan URL for Malicious Content</h4>
            </div>
            <div class="card-body">
                <form id="scanForm">
                    <div class="mb-3">
                        <label for="url" class="form-label">URL to Scan</label>
                        <input type="url" class="form-control" id="url" name="url" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_subject" class="form-label">Email Subject (Optional)</label>
                        <input type="text" class="form-control" id="email_subject" name="email_subject">
                    </div>
                    <div class="mb-3">
                        <label for="email_sender" class="form-label">Email Sender (Optional)</label>
                        <input type="email" class="form-control" id="email_sender" name="email_sender">
                    </div>
                    <button type="submit" class="btn btn-primary">Scan URL</button>
                </form>

                <div id="scanResult" class="mt-4" style="display: none;">
                    <div class="alert" role="alert">
                        <h5 class="alert-heading">Scan Results</h5>
                        <div id="resultContent"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($scanResults->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Recent Scans</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scanResults as $result)
                            <tr>
                                <td>{{ Str::limit($result->url, 50) }}</td>
                                <td>
                                    @if($result->is_malicious)
                                        <span class="badge bg-danger">Malicious</span>
                                    @else
                                        <span class="badge bg-success">Safe</span>
                                    @endif
                                </td>
                                <td>{{ $result->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#scanForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("scanner.scan") }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                const result = response.result;
                const alertClass = result.is_malicious ? 'alert-danger' : 'alert-success';
                const statusText = result.is_malicious ? 'Malicious' : 'Safe';
                
                $('#scanResult').show();
                $('#scanResult .alert')
                    .removeClass('alert-danger alert-success')
                    .addClass(alertClass);
                
                $('#resultContent').html(`
                    <p><strong>Status:</strong> ${statusText}</p>
                    <p><strong>URL:</strong> ${result.url}</p>
                    <p><strong>Details:</strong></p>
                    <pre>${result.threat_details}</pre>
                `);
                
                // Reload page after 2 seconds to show updated recent scans
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                $('#scanResult').show();
                $('#scanResult .alert')
                    .removeClass('alert-danger alert-success')
                    .addClass('alert-danger');
                $('#resultContent').html('Error scanning URL. Please try again.');
            }
        });
    });
});
</script>
@endpush 