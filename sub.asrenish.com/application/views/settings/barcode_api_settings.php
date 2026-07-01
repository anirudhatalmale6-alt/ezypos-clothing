<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">LabelJoy API Settings</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20">API Key</h4>
                    <p class="text-muted">This key is required for LabelJoy to access the barcode data API. Keep it secure.</p>

                    <div class="form-group">
                        <label>Current API Key</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apiKeyDisplay" readonly placeholder="No API key generated yet" style="font-family: monospace;">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" id="btnCopyKey" title="Copy to clipboard"><i class="fa fa-copy"></i></button>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="btnGenerateKey"><i class="fa fa-refresh"></i> Generate New Key</button>
                    <p class="text-danger m-t-10" style="font-size: 12px;">Generating a new key will invalidate the old one. Update LabelJoy configuration after regenerating.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20">API Base URL</h4>
                    <div class="form-group">
                        <label>Your API Base URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apiBaseUrl" readonly style="font-family: monospace;" value="<?php echo base_url(); ?>barcode-api/">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" id="btnCopyUrl" title="Copy to clipboard"><i class="fa fa-copy"></i></button>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted" style="font-size: 12px;">Use this base URL when configuring LabelJoy's data source connection.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-20">API Documentation</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:80px;">Method</th>
                                    <th style="width:250px;">Endpoint</th>
                                    <th>Description</th>
                                    <th>Parameters</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-success">GET</span></td>
                                    <td><code>/barcode-api/items</code></td>
                                    <td>List all active items with barcode data, price, stock, category</td>
                                    <td><code>search</code>, <code>category</code>, <code>store_id</code> (all optional query params)</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">GET</span></td>
                                    <td><code>/barcode-api/item/{id}</code></td>
                                    <td>Get single item barcode data by item ID</td>
                                    <td>Item ID in URL path</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-primary">POST</span></td>
                                    <td><code>/barcode-api/batch</code></td>
                                    <td>Get barcode data for multiple items with label counts (grouped)</td>
                                    <td>JSON body: <code>{"items": [{"item_id": 1, "quantity": 10}]}</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">GET</span>/<span class="badge badge-primary">POST</span></td>
                                    <td><code>/barcode-api/batch-flat</code></td>
                                    <td>Flat label list - one row per label for template binding</td>
                                    <td>GET: <code>?items=1:10,2:20</code> or POST body same as /batch</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">GET</span></td>
                                    <td><code>/barcode-api/categories</code></td>
                                    <td>List all active categories</td>
                                    <td>None</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">GET</span></td>
                                    <td><code>/barcode-api/stores</code></td>
                                    <td>List all active stores</td>
                                    <td>None</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-success">GET</span></td>
                                    <td><code>/barcode-api/info</code></td>
                                    <td>API info and endpoint documentation (JSON)</td>
                                    <td>None (no auth required)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="m-t-20">Authentication</h5>
                    <p>Pass the API key using one of these methods:</p>
                    <ul>
                        <li>Header: <code>X-API-Key: YOUR_KEY</code></li>
                        <li>Header: <code>Authorization: Bearer YOUR_KEY</code></li>
                        <li>Query parameter: <code>?api_key=YOUR_KEY</code></li>
                    </ul>

                    <h5 class="m-t-20">Quick Test</h5>
                    <button class="btn btn-info btn-sm" id="btnTestApi"><i class="fa fa-play"></i> Test API Connection</button>
                    <div id="testResult" class="m-t-10" style="display:none;"></div>

                    <h5 class="m-t-20">Example: Batch Request</h5>
                    <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; font-size: 12px;">POST /barcode-api/batch
Content-Type: application/json
X-API-Key: YOUR_API_KEY

{
  "items": [
    {"item_id": 1, "quantity": 10},
    {"item_id": 2, "quantity": 20},
    {"item_code": "ITEM003", "quantity": 5}
  ]
}

Response:
{
  "success": true,
  "total_items": 3,
  "total_labels": 35,
  "labels": [
    {
      "item_id": 1,
      "item_code": "ITEM001",
      "barcode_value": "ITEM001",
      "item_name": "Sample Item",
      "selling_price": 1500.00,
      "brand": "BrandName",
      "category": "Shirts",
      "label_count": 10
    }
  ]
}</pre>

                    <h5 class="m-t-20">Example: Flat Labels (for LabelJoy Template Binding)</h5>
                    <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; font-size: 12px;">GET /barcode-api/batch-flat?items=1:10,2:20&api_key=YOUR_API_KEY

Response:
{
  "success": true,
  "total_labels": 30,
  "labels": [
    {"item_code": "ITEM001", "barcode_value": "ITEM001", "item_name": "Sample Item", "selling_price": 1500.00, ...},
    {"item_code": "ITEM001", "barcode_value": "ITEM001", "item_name": "Sample Item", "selling_price": 1500.00, ...},
    ... (10 rows for ITEM001, 20 rows for ITEM002)
  ]
}</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

$(function(){
    // Load current API key
    $.getJSON(BASE_URL + 'barcode-api/get-key', function(res){
        if(res.success && res.api_key){
            $('#apiKeyDisplay').val(res.api_key);
        } else {
            $('#apiKeyDisplay').attr('placeholder', 'No API key generated yet. Click Generate to create one.');
        }
    });

    // Generate new key
    $('#btnGenerateKey').click(function(){
        if(!confirm('Generate a new API key? The old key will stop working immediately.')){
            return;
        }
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating...');
        $.post(BASE_URL + 'barcode-api/generate-key', function(res){
            if(res.success){
                $('#apiKeyDisplay').val(res.api_key);
                swal({type:'success', title:'API Key Generated', text:'New key has been set. Update your LabelJoy configuration with this key.'});
            } else {
                swal({type:'error', title:'Error', text: res.error || 'Failed to generate key'});
            }
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Generate New Key');
        }, 'json').fail(function(){
            swal({type:'error', title:'Error', text:'Failed to generate key'});
            btn.prop('disabled', false).html('<i class="fa fa-refresh"></i> Generate New Key');
        });
    });

    // Copy key
    $('#btnCopyKey').click(function(){
        var keyInput = document.getElementById('apiKeyDisplay');
        keyInput.select();
        document.execCommand('copy');
        swal({type:'success', title:'Copied!', text:'API key copied to clipboard', timer: 1500});
    });

    // Copy URL
    $('#btnCopyUrl').click(function(){
        var urlInput = document.getElementById('apiBaseUrl');
        urlInput.select();
        document.execCommand('copy');
        swal({type:'success', title:'Copied!', text:'API URL copied to clipboard', timer: 1500});
    });

    // Test API
    $('#btnTestApi').click(function(){
        var key = $('#apiKeyDisplay').val();
        if(!key){
            swal({type:'warning', title:'No API Key', text:'Generate an API key first before testing.'});
            return;
        }
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Testing...');
        $('#testResult').show();

        $.ajax({
            url: BASE_URL + 'barcode-api/items?api_key=' + encodeURIComponent(key),
            method: 'GET',
            dataType: 'json',
            success: function(res){
                if(res.success){
                    $('#testResult').html('<div class="alert alert-success"><strong>Connection OK!</strong> Found ' + res.count + ' items in the system.</div>');
                } else {
                    $('#testResult').html('<div class="alert alert-danger"><strong>Error:</strong> ' + (res.error || 'Unknown error') + '</div>');
                }
            },
            error: function(xhr){
                var msg = 'Connection failed';
                try { msg = JSON.parse(xhr.responseText).error || msg; } catch(e){}
                $('#testResult').html('<div class="alert alert-danger"><strong>Error:</strong> ' + msg + '</div>');
            },
            complete: function(){
                btn.prop('disabled', false).html('<i class="fa fa-play"></i> Test API Connection');
            }
        });
    });
});
</script>
