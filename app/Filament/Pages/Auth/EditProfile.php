<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
//    protected string $view = 'filament.pages.auth.edit-profile';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar_url')
                    ->alignCenter()
                    ->hiddenLabel()
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->imageEditor()
                    ->maxSize(2048),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return parent::getFormContentComponent();
    }
}
