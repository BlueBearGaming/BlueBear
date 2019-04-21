namespace :symfony do
  namespace :assetic do
    desc "Assetic dump"
    task :dump do
      on release_roles(:all) do
        symfony_console "assetic:dump", fetch(:symfony_default_flags)
      end
    end
  end

  before 'deploy:publishing', 'symfony:assetic:dump'
end
