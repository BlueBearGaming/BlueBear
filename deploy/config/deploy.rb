set :application, "bluebear"
set :repo_url, "git@github.com:BlueBearGaming/BlueBear.git"
set :ssh_options, { :forward_agent => true }

append :linked_files, "app/config/parameters.yml"
append :linked_dirs, "app/logs", "web/resources", "web/jikpoze"
set :symfony_console_path, 'app/console'
set :log_path, 'app/logs'
set :cache_path, 'app/cache'

# set :default_env, { path: "~/.composer/vendor/bin:$PATH" }

set :keep_releases, 3

set :symfony_console_flags, "--no-debug --env=prod"

after :deploy, "phpfpm:restart"
