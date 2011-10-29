<?php 
if (isset($_SESSION['valid_user']) && $_SESSION['access_privileges'] == 'bonus'){
    ?>
<h1>Future Updates</h1>
<h2>Stuff that is in the works</h2>
<ol>
    <li>Switch Bittorrent Clients</li>
    <p>
        My latest mission is to switch my server's bittorrent clients from Deluge to Transmission.
        I'm beginning to not be too happy with Deluge and it's not fitting the needs of this website. For some reason
        there's an error in it where it won't correctly update it's announce data, and scrape data.
        It's probably a problem with libtorrent which is Deluge's backend for updating announce data. 
        Unfortunately this is something Deluge can't fix because it's part of a separate open source 
        project. This in the end is something that holds Deluge back in features, and maybe
        something they should think about implementing themselves to bring under their project's control.
        So now, I'm in the process of figuring out how to properly set up Transmission, which will 
        work perfectly for the web site with its command line interface that will make it easier
        to control, and bend to my/our will....muahahaahaaa!!!!! j/k
    </p>
</ol>
    <?php
}
?>
