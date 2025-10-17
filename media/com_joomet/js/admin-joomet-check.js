document.addEventListener( 'DOMContentLoaded', function() {
    const $commentRowsSelector = document.querySelector('#show_comment_rows');
    const $emptyRowsSelector = document.querySelector('#show_empty_rows');

    if($commentRowsSelector){
        $commentRowsSelector.addEventListener('change', toggleCommentRows);
    }

    if($emptyRowsSelector){
        $emptyRowsSelector.addEventListener('change', toggleEmptyRows);
    }

    toggleCommentRows();
    toggleEmptyRows();

    function toggleCommentRows(){
        const $commentRows = document.querySelectorAll('tr[data-comment-row="1"]')
        if($commentRowsSelector.checked){
            $commentRows.forEach(row => {
                row.classList.remove('d-none');
            })
        }else{
            $commentRows.forEach(row => {
                row.classList.add('d-none');
            })
        }
    }

    function toggleEmptyRows(){
        const $emptyRows = document.querySelectorAll('tr[data-empty-row="1"]');
        if($emptyRowsSelector.checked){
            $emptyRows.forEach(row => {
                row.classList.remove('d-none');
            })
        }else{
            $emptyRows.forEach(row => {
                row.classList.add('d-none');
            })
        }
    }


})