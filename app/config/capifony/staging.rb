set :domain,               "ks3313894.kimsufi.com"
set :deploy_to,            "/home/bluebear/www/"
set :app_path,             "app"
set :user,                 "bluebear"
set :branch,               "master"
ssh_options[:port] =       "22"

role :web,                 domain
role :app,                 domain
role :db,                  domain, :primary => true

logger.level = Logger::MAX_LEVEL