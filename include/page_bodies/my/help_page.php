<?php 
if (isset($_SESSION['valid_user']) && $_SESSION['access_privileges'] == 'bonus'){    
?>
<h1>You need help???</h1>
<h2>How things work</h2>
<p>
    Things work like this: When you click on a link for a file, what is downloaded is a 
    .torrent file. To use a torrent file you first need a bittorrent client.
    Once you get the torrent file, you can open it using your bittorrent client and the P2P(peer-to-peer) downloading should begin.
    The primary tracker used is http://tracker.digi-tain.com:80 and for security reasons I would recommend only using that tracker.
    You should also take note that if your ip address changes you're going to have to log into the website, so
    your ip address can be relogged into the system, and you can regain access to the tracker. I used ip addresses as the
    primary method of access control to the tracker, which in my mind is a worthy enough security measure to protect 
    against invalid users. I would also recommend turning off Peer Exchange, and DHT for these torrents, also for 
    security reasons. Local Peer
    Discovery is fine left on unless for some reason your concerned about a malicious person on your home network
    ratting you out for illegal downloads.
</p>
<p>
    If you want more speed and don't mind living on the edge ;), a few files have extra trackers that are available for use.
    These come in a .txt file and can be simply copy and pasted into the add trackers prompt on your bittorrent client. 
</p>
<div id="bt_client_help">    
    <h2>Bittorrent Client Recommendations</h2>
    <p>
        I have a few recommendations for bittorrent clients that are my favorites.
    </p>
    <h3>For All OS's:</h3>
        <h4>Vuze</h4>
            <p>
                Vuze is a great client and has the most features of them all. If Vuze can't do it
                then there's no chance the other clients can. No extra setup is needed for this
                client to work properly with the torrents on this site. My only dislike about it is that they've 
                started putting in too much nagging for donations in the program with devious little
                buttons you have to look for to close the donation or upgrade prompts. I'm very against 
                devious programs to the point that I no longer use it, but due to it's usefulness
                I can't ignore giving it a recommendation.
            </p>   
    <h3>For Windows:</h3>
        <h4>µtorrent/bittorrent(the orginal bittorrent client)</h4>
            <p>
                For windows I had a tough choice between clients. To work with the torrents on this site there is a setting
                that has to be tweaked in order for this client to work properly, but it <em>is</em> the original bittorrent client
                and it works great with all the features you truly need. It has multi tracker support and is very simple 
                in design along with a small system resource footprint.
            </p>
        <h4 class="outdated">Deluge</h4>
            <p>
                This is my second choice for windows. Best of all it doesn't require any additional set up to work with 
                the torrents on this site (in fact this is the client that the digi-tain.com server uses). It has some interesting
                features such as the interface being completely separate from the base program meaning that it can be running
                in the background and you can access it via the web or another client on another computer. It's great for keeping
                track of things on a remote computer ;) I get the impression that this client is geared more towards
                legitimate purposes, (rather than the larger illegal 'illegitimate' purpose most clients are geared towards)
                because it has some nice remote capabilities (and partially because the download for it is hosted from OSU). 
                The only downside is that it doesn't have multi tracker support, and 
                isn't very robust at finding clients from small public torrents. To access the small private torrents off of this site
                it should work marvelously though.
            </p>
            <p>
                I can no longer recommend Deluge for a bittorrent client. It works, but has some minor issues with trackers. It's
                usable, but with so many other suitable solutions out there, there's no point in holding on to it. I'm in
                the process now of moving my server's bittorrent client to transmission.
            </p>
    <h3>For Mac or Linux:</h3>
        <h4>Transmission</h4>
            <p>
                I only have a little experience with this client, but it seemed to work great, while bringing only
                the utmost necessary features to the table. It comes by default on the Ubuntu distribution of the Linux.
                This client will work perfectly with the torrents on this site.
            </p> 
    <h2>µtorrent/bittorrent fix</h2>
    <p>
        µtorrent is set up for best use on public torrents. Unfortunately this conflicts with when you're only sharing
        amongst a small group of people. To get it to work right and connect with the peer located on the digi-tain.com
        server you must allow multiple connections from the same peer. To do this, go to settings and click on the advanced
        tab on the side. In there you see a variety of options and right at the beginning is "bt.allow_same_ip". 
        Change this to true (by default false) and you should be all set.
    </p>
    <h2>Sharing Files Help</h2>
    <p>
        Part of my motivation for creating this feature on my website was for everyone to be able to contribute to the file 
        sharing process and have it not be full of just my creations. Creating a torrent is a pretty easy process from
        your torrent client (if you don't know how just google it). The only really technical part is where you add the tracker
        information. To your torrents be sure to add http://tracker.digi-tain.com:80/announce as a tracker. You may also add
        public trackers (or leave old trackers from a public torrent) such as http://tracker.publicbt.com:80/announce or 
        udp://tracker.openbittorrent.com:80/announce. I originally was going to use these trackers in my implementation
        rather than creating my own tracker (which is more secure anyways), but I've since discovered that these trackers can often mysteriously dissappear
        from the internet, and they'll stay down for sometimes a week at a time. Just plain undependable. Peer exchange
        and DHT are what really keeps public torrents running. If you goof up, it's fine because upon upload the
        file is rewritten to use the digi-tain.com tracker, and all you have to do is fix the tracker from your side.
        You should also know that the creator of the bittorrent file is changed to your username.
        When the file is uploaded, the 
        bittorrent client on my server should automatically start downloading it from you so it will be constantly
        available even when your offline. Be aware that you are responsible for aiding in the file sharing process
        and it would be helpful if you seeded as much as possible. Due to the meager 50 kbyte/s upload speed of my home
        internet connection P2P is the most effective way to exchange files on the server rather than doing a straight download.
    </p>
</div>

<?php
}
?>