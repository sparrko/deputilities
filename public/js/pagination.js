function paginationSubmit($id, $reset) {

    if ($reset) $($id).append('<select style="display: none" name="pPage"><option value="' + $('#pPage').val() + '" selected></option></select>');
    $($id).append('<select style="display: none" name="pCount"><option value="' + $('#pCount').val() + '" selected></option></select>');
    $($id).submit();
}
