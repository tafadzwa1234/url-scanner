@extends('layouts.app')

@section('title', 'Scan History')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Scan History</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Email Subject</th>
                                <th>Email Sender</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scanResults as $result)
                            <tr>
                                <td>{{ Str::limit($result->url, 50) }}</td>
                                <td>{{ $result->email_subject ?? 'N/A' }}</td>
                                <td>{{ $result->email_sender ?? 'N/A' }}</td>
                                <td>
                                    @if($result->is_malicious)
                                        <span class="badge bg-danger">Malicious</span>
                                    @else
                                        <span class="badge bg-success">Safe</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $result->id }}">
                                        View Details
                                    </button>
                                </td>
                                <td>{{ $result->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="detailsModal{{ $result->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Scan Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>URL:</strong> {{ $result->url }}</p>
                                            <p><strong>Status:</strong> 
                                                @if($result->is_malicious)
                                                    <span class="badge bg-danger">Malicious</span>
                                                @else
                                                    <span class="badge bg-success">Safe</span>
                                                @endif
                                            </p>
                                            <p><strong>Threat Details:</strong></p>
                                            <pre>{{ $result->threat_details }}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $scanResults->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 