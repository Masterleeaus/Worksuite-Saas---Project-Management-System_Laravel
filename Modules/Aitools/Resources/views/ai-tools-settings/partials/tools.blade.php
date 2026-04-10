<div class="ai-tool-card">
    <div class="ai-tool-header">
        <div class="ai-tool-title">
            <div class="ai-tool-icon"><i class="fa fa-wrench"></i></div>
            <h5>Tools Registry</h5>
        </div>
    </div>

    <form method="POST" action="{{ route('ai-tools.tools.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Tool Name</label>
                <input name="tool_name" class="form-control" placeholder="example_tool" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Title</label>
                <input name="title" class="form-control" placeholder="Example Tool">
            </div>
            <div class="col-md-4 form-group">
                <label>Risk Level</label>
                <select name="risk_level" class="form-control">
                    <option value="low">low</option>
                    <option value="medium">medium</option>
                    <option value="high">high</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <input name="description" class="form-control" placeholder="What does this tool do?">
        </div>

        <div class="form-group">
            <label>Input Schema (JSON, optional)</label>
            <textarea name="input_schema" class="form-control" rows="2" placeholder='{"required":["id"]}'></textarea>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_enabled" value="1" checked> Enabled</label>
        </div>

        <button class="btn btn-primary">Save Tool</button>
    </form>

    <hr>

    <h6 class="mb-2">Registered Tools</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Tool</th><th>Risk</th><th>Enabled</th></tr></thead>
            <tbody>
                @foreach(($tools ?? []) as $t)
                    <tr>
                        <td>{{ $t->tool_name }}</td>
                        <td>{{ $t->risk_level }}</td>
                        <td>{{ $t->is_enabled ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
