load 'deploy' if respond_to?(:namespace) # cap2 differentiator

require 'capifony_symfony2'

set :stages, %w(staging)
set :default_stage, "staging"
set :stage_dir,     "app/config/capifony"

set :application,          "BlueBear"
set :use_sudo,             false
set :controllers_to_clear, ['none']

set :writable_dirs,        ["app/cache", "app/logs"]
set :webserver_user,      "bluebear"
set :permission_method,   :acl
set :use_set_permissions,  true

set :dump_assetic_assets, true
set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", app_path + "/bin", web_path + "/uploads", "vendor"]
set :use_composer,        true
set :update_vendors,      false

set :repository,       "https://bluebear@github.com/CleverGaming/BlueBear.git"
set :scm,              :git
set :deploy_via,       :remote_cache
set :model_manager,    "doctrine"

set :keep_releases,  2
after "deploy:update",     "deploy:cleanup"

require 'capistrano/ext/multistage'