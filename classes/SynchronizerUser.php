<?php

use Respect\Validation\Validator as v;

/**
 * @author Michael Fernandes <michael.rodrigues@icmbio.gov.br>
 */
final class SynchronizerUser {

    /**
     * @var SynchronizableUser
     */
    private $_sync = NULL;

    /**
     * @return void
     * @param SynchronizableUser
     */
    private function __construct(SynchronizableUser $sync) {
        $this->_sync = $sync;
    }

    /**
     * @return SynchronizerUser
     * @param SynchronizableUser
     */
    public static function factory(SynchronizableUser $sync) {
        return new self($sync);
    }

    /**
     * @return boolean
     * @param stdClass $user
     */
    public function updateUser(stdClass $user) {
        try {

            $response = CFModelUsuario::factory()->update((array) $user);

            if ($response) {
                $this->associateUnitsWithUser($user);
            }

            return !empty($response);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * return boolean
     * @param stdClass $user
     */
    public function createUser(stdClass $user) {
        try {
            CFModelUsuario::factory()->insert((array) $user, false) ? true : false;
            $this->associateUnitsWithUser($user);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return boolean 
     * @param stdClass $user
     */
    public function checkUser(stdClass $user) {
        try {
            $user = CFModelUsuario::factory()->find($user->ID);
            return empty($user) ? false : true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return User
     * @param string $identifier
     */
    public function loadUser($identifier) {
        return $this->_sync->loadUser($identifier);
    }

    /**
     * @return boolean
     * @param stdClass $user
     */ public function isValid($user) {

        $userValidator = v::attribute('ID', v::numeric())
                ->attribute('USUARIO', v ::string()->notEmpty()->noWhitespace())
                ->attribute('NOME', v::string()->notEmpty())
                ->attribute('EMAIL', v::email())
                ->attribute('CPF', v::cpf())
                ->attribute('STATUS', v::numeric())
                ->attribute('UNIDADES', v::arr())

        ;

        return $userValidator->validate($user);
    }

    /**
     * @return void
     * @param stdClass $user
     */
    public function associateUnitsWithUser($user) {

        CFModelUsuarioUnidade::factory()->disassociateAllByUserId($user->ID);

        foreach ($user->UNIDADES as $unidade) {
            if (CFModelUsuarioUnidade::factory()->isExists($user->ID, $unidade)) {
                CFModelUsuarioUnidade::factory()->updateUserAssociationWithUnit($user->ID, $unidade, 1);
            } else {
                CFModelUsuarioUnidade::factory()->createUserAssociationWithUnit($user->ID, $unidade);
            }
        }
    }

}
