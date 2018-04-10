<div class="container-fluid">
    <div class="row">
        <div class="col">
            You are logged in!
            <h2>Welcome {{ session('social_user')->name}}</h2>
        </div>
        <div class="col">
            <img src="{{ session('social_user')->avatar}}" alt="..." class="rounded float-right">
        </div>
    </div>
    <div class="row">
                <div class="col">
                    <h6>Followers: {{session('twitter_data')->followers_count}}</h6>
                </div>
                <div class="col">
                    <h6>Friends Ammo: {{session('twitter_data')->friends_count}}</h6>
                </div>
                <div class="col">
                    <h6>Joined at: {{ date('d-m-Y', strtotime(session('twitter_data')->created_at))}}</h6>                    
                </div>
                <div class="col">
                    <h6>Favorites: {{session('twitter_data')->favourites_count}}</h6>                    
                </div>
                <div class="col">
                    <h6>Language: {{session('twitter_data')->lang}}</h6>                    
                </div>
            </div>
    {{ Debugbar::info(session('twitter_data'))}}
    
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Posted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('twitter_data')->tweets as $tweet)
                        <tr>
                            <td>{{$tweet->text}}</td>
                            <td>{{ date('d-m-Y', strtotime($tweet->created_at))}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>