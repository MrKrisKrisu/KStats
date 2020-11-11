@if($track->popularity > 80)
    <label class="badge badge-sm badge-success" data-toggle="tooltip" data-placement="top"
           title="Dieser Track ist aktuell sehr populär.">
        <i class="fas fa-chart-line"></i> In den Trends
    </label>
@elseif($track->popularity > 60)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="Dieser Track wird aktuell von vielen Usern angehört.">
        <i class="far fa-thumbs-up"></i> Beliebter Track
    </label>
@elseif($track->popularity < 15)
    <label class="badge badge-sm badge-danger" data-toggle="tooltip" data-placement="top"
           title="Dieser Track ist eher weniger bekannt, bzw. wird aktuell sehr wenig abgespielt.">
        <i class="fas fa-question"></i> Vergessener Track
    </label>
@endif

@if($track->valence > 0.4)
    <label class="badge badge-sm badge-success" data-toggle="tooltip" data-placement="top"
           title="Dieses Lied ist macht gute Laune.">
        <i class="far fa-smile"></i> Happy
    </label>
@elseif($track->valence < 0.3)
    <label class="badge badge-sm badge-danger" data-toggle="tooltip" data-placement="top"
           title="Dieses Lied hat eher eine traurige bzw. aggressive Stimmung.">
        <i class="far fa-sad-tear"></i> Traurig / Aggressiv
    </label>
@endif

@if($track->danceability > 0.5)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="Zu diesem Lied kann man gut tanzen.">
        <i class="fas fa-walking"></i> Zum tanzen
    </label>
@endif

@if($track->speechiness > 0.6)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="In diesem Track wird sehr viel gesprochen.">
        <i class="far fa-comments fa-2x"></i>
    </label>
@elseif($track->speechiness > 0.4)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="In diesem Track wird viel gesprochen.">
        <i class="far fa-comment fa-2x"></i>
    </label>
@endif