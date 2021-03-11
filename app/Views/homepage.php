<main class="col-md-6 flex-md-nowrap">
    
    <form action="<?=site_url("adjectivesApp/search")?>" method="post" id="searchForm">
        <h3>Enter a topic, and find out how the twitterverse feels about it!</h3>
        <div class="form-group col-md-12">
            <div class="mx-auto col-md-6">
                <small class="info">Search Term</small>
                <input type="text" name="term" id="term" class="form-control">
                <br>
                <input type="submit" class="btn btn-primary" value="Search!">
            </div>            
        </div>
    </form>
</main>