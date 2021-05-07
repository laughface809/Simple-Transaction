 DB::beginTransaction();
        try {
            $user = User::create([
                'name'              => $request->name,
                'sdmkaryawan_id'    => $request->sdmkaryawan_id,
                'username'          => $request->username,
                'email'             => $request->email,
                'nomp'              => $request->nomp,
                'password'          => Hash::make($request->password),
                'activation_token'  => Str::random(60),                     //generate str random untuk token aktifasi
                'locked'            => $request->locked
            ]);

            // register role untuk user
            // 1. Maintenance
            // 2. Administrator
            // 3. Customer Service
            // 4. Accounting
            // 5. Lawyer
            // 6.Client
            // $role = $request->role;
            // $role = 3;
            $role = $request->role;
            $user->assignRole($role);

            DB::commit();

            // kirim email verifikasi
            // $user->notify(new SignupActivate($user));

            return new UserResource($user);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ], 404);
        }
