<?php

namespace App\Model;

class User extends Base
{

    /**
     *
     * @var string
     * @Column(column="first_name", type="string", length=128, nullable=false)
     */
    public $first_name;

    /**
     *
     * @var string
     * @Column(column="last_name", type="string", length=128, nullable=false)
     */
    public $last_name;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(column="login", type="string", length=128, nullable=false)
     */
    public $login;

    /**
     *
     * @var string
     * @Column(column="pass", type="string", length=128, nullable=false)
     */
    public $pass;

    /**
     *
     * @var integer
     * @Column(column="failed_login_attempt", type="integer", length=1, nullable=false)
     */
    public $failed_login_attempt;

    /**
     *
     * @var string
     * @Column(column="locked_date", type="string", nullable=true)
     */
    public $locked_date;

}
