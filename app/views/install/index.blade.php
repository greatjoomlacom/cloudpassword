<div>
    <p>There are some prerequisites your server has to match.</p>
    @if(!$passed)
        <p class="alert alert-danger">We are sorry but your server does not match the minimal prerequirements. You can see the result bellow.<br />Please fix the configuration first, then reload this page.</p>
        <p><br /></p>
    @endif

    <div class="col-lg-8 margin-center">
        <h1>Server</h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Required</th>
                    <th>Your value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PHP version</td>
                    <td>>= 5.3.7</td>
                    <td>{{ $php_version }}</td>
                    <td>
                        @if($php_version_status)
                            <span class="label label-success">Passed</span>
                        @else
                            <span class="label label-danger">Failed</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>MySQL support</td>
                    <td>recommended<br />>= 5.0.4</td>
                    <td>yes</td>
                    <td>
                        @if($mysql_support)
                            <span class="label label-success">Passed</span>
                        @else
                            <span class="label label-danger">Failed</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>memory_limit</td>
                    <td>>= 32M</td>
                    <td>
                        <?php if ($memory_limit === null): ?>
                            unknown
                        <?php else: ?>
                            <?php echo $memory_limit; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($memory_limit_status and $memory_limit): ?>
                            <span class="label label-success">Passed</span>
                        <?php elseif(!$memory_limit_status and $memory_limit): ?>
                            <span class="label label-danger">Failed</span>
                        <?php else: ?>
                            <span class="label label-default">Unknown</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>mcrypt</td>
                    <td>yes</td>
                    <td><?php echo ($mcrypt ? 'yes' : 'no'); ?></td>
                    <td>
                        <?php if ($mcrypt): ?>
                            <span class="label label-success">Passed</span>
                        <?php else: ?>
                            <span class="label label-danger">Failed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><br /></p>
        <h2>Directories & files</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Directories & Files permissions</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($writable_dirs as $dir=>$status)
                    <tr>
                        <td>{{ $dir }}</td>
                        <td>
                            @if($status)
                            <span class="label label-success">Writable</span>
                            @else
                                <span class="label label-danger">Unwritable</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($passed)
            <a href="{{ URL::action('InstallController@getEstablishConnection') }}" class="btn btn-primary">Continue</a>
        @endif
    </div>
</div>