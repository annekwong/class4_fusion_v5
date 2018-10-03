<script language="JavaScript" type="text/javascript">
//<![CDATA[
var lastId = 0;
var preset_types = {"auto":"automatic","manual":"manual"};
function addItem(type, row)
{
    lastId++;
    if (!row || !row['id']) {
        row = {
            'type': 'manual',
            'origin': (type == 'nt' ? 'dr' : type),
            'order': '1'
        };
    }
    
    // fix row values
    for (k in row) { if (row[k] == null) row[k] = ''; }
    
    // prepare row
    var tRow = $('#tpl-'+type).clone();
    tRow.attr('id', 'row-'+lastId).show();
    
    // set names / values
    tRow.find('input,select').each(function () {
        var name = $(this).attr('name').substring(1).replace('%n', lastId);
        var field = name.substring(name.lastIndexOf('[')+1, name.length-1);
        $(this).attr('id', 'row-'+lastId+'-'+field);
        $(this).attr('name', name);
        
        if ($(this).is('input[type=checkbox]')) {
            $(this).attr('checked', (row[field] != null && row[field]));
        } else {
            $(this).val(row[field]);
        }
    });
    
    // set spans
    tRow.find('small[rel=input],span[rel=input]').each(function () {
        $(this).css('display', 'block');
        var field = $(this).text();
        if (field == 'type') {
            $(this).text(preset_types[row['type']]);
        } else {
            $(this).text(row[field] ? row[field] : '');
        }
    });
    
    // code / name searchs
    tRow.find('img[rel=code-search]').click(function () {
        findCode($(this).closest('tr').attr('id'), $(this).attr('x:type'), $(this).attr('x:field'));
    }).closest('input').bind('dblclick', function () {
        findCode($(this).closest('tr').attr('id'), $(this).attr('x:type'), $(this).attr('x:field'));
    });
    
    // client searchs
    tRow.find('img[rel=client-search],input[rel=client-search]').click(function () {
        findClient($(this).closest('tr').attr('id'));
    });
    
    // remove of the row
    tRow.find('a[rel=delete]').click(function () {
        $(this).closest('tr').remove();
        return false;
    });
    
    if (row['id']) {
        tRow.appendTo($('#rows-'+type));
    } else {
        tRow.prependTo($('#rows-'+type));
    }
    
    // styles
    if (!row['id']) {
        initForms(tRow);
        initList();
    }
}


var cd = {"orig":null,"term":null};
function findCode(rowId, origin, type) 
{
    var _ss_ids = {};
    if (type == 'code' || !type) {
        _ss_ids['code'] = rowId+'-code';
    }
    if (type == 'code_name' || !type) {
        _ss_ids['code_name'] = rowId+'-code_name';
    }
	
    // check if we have query
    if (type == 'code_name') {
        _q = $('#'+_ss_ids['code_name']).val();
    }
	
    ss_code(cd[origin], _ss_ids, _q);
}
function findClient(rowId) 
{
    var _ss_ids = {
        'id_clients': rowId+'-term_id_clients',
        'id_clients_name': rowId+'-terminator',
        'id_accounts': rowId+'-term_id_accounts'
    };
    ss_client(3, _ss_ids);
}


//]]>
</script>
