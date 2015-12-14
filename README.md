# Slack
Slack API 某所公開用


## temp memo.

In `app/Plugin/Slack/config/bootstrap.php`
```
namespace Slack;
define(__NAMESPACE__.'\API_TOKEN','xxxx-xxx~~');
```

In `app/config/bootstrap.php`
```
CakePlugin::load('Slack',['bootstrap'=>true]);
```

