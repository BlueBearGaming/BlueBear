load 'deploy' if respond_to?(:namespace) # cap2 differentiator

require 'capifony_symfony2'

set :stages, %w(staging vince)
set :default_stage, "staging"
set :stage_dir,     "app/config/capifony"

set :application,          "BlueBear"
set :use_sudo,             false
set :controllers_to_clear, ['none']
set :composer_options,  "--verbose --prefer-dist --optimize-autoloader --no-progress"

set :writable_dirs,        ["app/cache", "app/logs"]
set :webserver_user,      "bluebear"
set :permission_method,   :acl
set :use_set_permissions,  true

set :dump_assetic_assets, true
set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", web_path + "/resources", "vendor", web_path + "/jikpoze", "node_modules"]
set :use_composer,        true
set :update_vendors,      false

set :repository,       "https://bluebear@github.com/CleverGaming/BlueBear.git"
set :scm,              :git
set :deploy_via,       :remote_cache
set :model_manager,    "doctrine"

set :keep_releases,  2
after "deploy:update",     "deploy:cleanup"

require 'capistrano/ext/multistage'
