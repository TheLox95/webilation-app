<div class="container">
            <div class="row">
                <div class="col">
                        You are logged in!
                        <h2>Welcome {{ session('social_user')->user['first_name']}} {{session('social_user')->user['last_name']}}</h2>
                </div>
                <div class="col">
                    <img src="{{ session('social_user')->avatar}}" alt="..." class="rounded float-right">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h6>{{ session('social_user')->email}}</h6>
                </div>
                <div class="col">
                    <h6>Birthday {{ session('social_user')->user['birthday']}}</h6>
                </div>
                <div class="col">
                    <h6>Friends ammo {{ session('social_user')->user['friends']['summary']['total_count']}}</h6>
                </div>
            </div>
            <div class="row">
                <div class="col">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Likes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Post</a>
                </li>
            </ul>
                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <h1>Liked pages</h1>
                <div class="row">
                    <div class="col">
                        @calendarchart('Likes', 'sales_div', ["width" => 1000, "height" => 800])
                    </div>
                </div>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Liked</th>
                    <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($likes_paginator as $like)
                    <tr>
                        <td>{{$like['name']}}</td>
                        <td>{{ date('d-m-Y', strtotime($like['created_time'])) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{-- Render the pagination links... --}}
            {!! $likes_paginator->render() !!}
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Story</th>
                    <th scope="col">Date</th>
                    <th scope="col">Go to</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($posts_paginator as $post)
                    <tr>
                    @if (!empty($post['story']))
                        <td>{{$post['story']}}</td>
                    @else
                        <td>{{$post['message']}}</td>
                    @endif
                        <td>{{ date('d-m-Y', strtotime($post['created_time'])) }}</td>
                        <td><a href="http://www.facebook.com/{{ $post['id'] }}">Link</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $posts_paginator->render() !!}            
                </div>
            </div>
                </div>
            </div>
</div>
                    