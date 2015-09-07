server 'ks3313894.kimsufi.com',
    user: 'bluebear',
    roles: %w{web app db},
    ssh_options: {
        user: 'user_name', # overrides user setting above
        keys: %w(/home/johnkrovitch/.ssh/id_rsa),
        forward_agent: false,
        auth_methods: %w(publickey password)
        # password: 'please use keys'
}
