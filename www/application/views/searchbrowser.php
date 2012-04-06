
<div id="searchbrowsebuttons">
    <p id="previous"> &lt&lt Previous page </p>
    <p id="next"> Next page &gt&gt </p> 
</div>

<script type="text/javascript">

$(document).ready(function()
{
    // Put the received user id's in a global using the template parser. 
    userIds = [0 {ids} , {id} {/ids} ];
    // Remove initial zero. 
    userIds.splice(0,1);
    
    // The current page of results. 
    currentPage = 0;
    // The total number of pages, equal to the number of id's divided by 6 (and rounded upwards). 
    nofPages = (userIds.length - 1) / 6 + 1;
    
    // Hide the previous button.
    $('#previous').hide();
    
    // Hide the nest button if there is only one page. 
    if(nofPages <= 1)
    {
        $('#next').hide();
    }
    
    // Function for getting a certain page through AJAX.
    function getPage(page)
    {
        // Determine id's on this page. 
        var ids = '';
        for(var i = page * 6; i < page * 6 + 6 && i < userIds.length; ++i)
        {
            ids += '/' + userIds[i];
        }
    
        // Do an AJAX request to update the displayed search results. 
        $('#searchresults').load('<?= base_url() . '/index.php/search/displayProfiles' ?>' + ids);
    }
    
    // Add mouse listeners to browser buttons. 
    $('#previous').click(function() 
    {
        if(currentPage > 0)
        {
            // Load previous page. 
            getPage(--currentPage);
    
            // Show next button again, if neccessary. 
            if($('#next').hidden())
            {
                $('#next').show();
            }
    
            // Hide previous button if back at first page. 
            if(currentPage == 0)
            {
                $('#previous').hide();
            }
        }
    }
    $('#next').click(function() 
    {
        if(currentPage < nofPages - 1)
        {
            // Load next page. 
            getPage(++currentPage);
    
            // Show prev button, if it was hidden before. 
            if($('#previous').hidden())
            {
                $('#previous').show();
            }
    
            // Hide next button if at last page. 
            if(currentPage == nofPages - 1)
            {
                $('#next').hide();
            }
        }
    }
}

</script> 
