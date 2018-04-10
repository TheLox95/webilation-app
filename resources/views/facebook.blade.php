<div class="container">
                        <div class="row">
                            <div class="col">
                                You are logged in!
                                <h2>Welcome {{ session('social_user')->user['first_name']}} {{session('social_user')->user['last_name']}}</h2>
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <h6>{{ session('social_user')->email}}</h6>
                                        </div>
                                        <div class="col">
                                            <h6>{{ session('social_user')->user['birthday']}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <img src="{{ session('social_user')->avatar}}" alt="..." class="rounded float-right">
                            </div>
                        </div>
                    </div>

                    <h1>Liked pages</h1>
                    

                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Liked</th>
                            <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach(session('social_user')->user['likes']['data'] as $like)
                            <tr>
                                <td>{{$like['name']}}</td>
                                <td>{{ date('d-m-Y', strtotime($like['created_time'])) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>