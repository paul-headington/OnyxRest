<?php
namespace OnyxRest\Model;

/**
 * RestResource model
 *
 * This is a class generated with Paul's Zend MVC Model Generator.
 *
 * @author Paul Headington
 * @createdOn
 * @license Copyright (c) 2014, Paul HeadingtonAll rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 * must display the following acknowledgement:
 * This product includes software developed by the <organization>.
 * 4. Neither the name of the <organization> nor the
 * names of its contributors may be used to endorse or promote products
 * derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY Paul Headington 'AS IS' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL Paul Headington BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class RestResource
{

    use \GetSet\SetterGetter;

    public $id = null;

    public $name = null;

    public $factory = null;

    public $tablename = null;

    public $auth = null;

    public $updatedon = null;

    public $postdate = null;

    const filter = null;

    protected $validation = array(
        'id' => array(
            'required' => false,
            'validator' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 1
                    )
                )
            )
        ),
        'name' => array(
            'required' => false,
            'validator' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        ),
        'factory' => array(
            'required' => false,
            'validator' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        ),
        'tablename' => array(
            'required' => false,
            'validator' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 3
                    )
                )
            )
        ),
        'auth' => array(
            'required' => false,
            'validator' => array(
                
            )
        ),
        'updatedon' => array(
            'required' => false,
            'validator' => array(
                
            )
        ),
        'postdate' => array(
            'required' => false,
            'validator' => array(
                
            )
        )
    );

    /**
     * build the model
     */
    public function __construct()
    {
    }

    /**
     * Validation selector
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * set array data to object
     */
    public function exchangeArray($data)
    {
        $this->id		= (isset($data["id"])) ? $data["id"] : null;
        $this->name		= (isset($data["name"])) ? $data["name"] : null;
        $this->factory		= (isset($data["factory"])) ? $data["factory"] : null;
        $this->tablename		= (isset($data["tablename"])) ? $data["tablename"] : null;
        $this->auth		= (isset($data["auth"])) ? $data["auth"] : null;
        $this->updatedon		= (isset($data["updatedon"])) ? $data["updatedon"] : null;
        $this->postdate		= (isset($data["postdate"])) ? $data["postdate"] : null;
    }


}

?>