<div class="progress" style="height: 20px; margin-bottom: 10px;">
    @if($valence == -1 || $valence == null)
        <div class="progress-bar bg-dark"
             style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
            {{__('no-data')}}
        </div>
    @else
        <div class="progress-bar @if($valence > 60) bg-success @elseif($valence > 30) bg-info @else bg-danger @endif @if($date->isToday()) progress-bar-animated @endif"
             style="width: {{$valence}}%" aria-valuenow="{{$valence}}" aria-valuemin="0" aria-valuemax="100">
            {{$date->isoFormat('dddd, DD.MM.YYYY')}}
        </div>
    @endif
</div>