<?php

use SilverStripeAustralia\Constraints\Extensions\ConstraintsExtension;

/**
 * @author <marcus@silverstripe.com.au>
 * @license BSD License http://www.silverstripe.org/bsd-license
 */
class TestConstraintsExtension extends SapphireTest
{
    
    public function testGetConstraints()
    {
        $page = new Page();
        
        $config = Config::inst();

        $config->update('Page', 'constraints', array('Title' => array('MinLengthConstraint')));
        
        $extended = new ConstraintsExtension();
        $extended->setOwner($page);
        $extended->injector = Injector::inst();
        
        $constraints = $extended->getConstraints();
        
        $this->assertEquals(1, count($constraints));
    }
    
    public function testMinLengthConstraint()
    {
        $minLength = Injector::inst()->create('SilverStripeAustralia\Constraints\Constraints\MinLengthConstraint', 'string', array('length' => 10));
        $this->assertFalse($minLength->holds());
        
        $minLength->setValue('a longer string');
        
        $this->assertTrue($minLength->holds());
        
        $page = new Page();
        $page->Title = 'This page title';
        
        $config = Config::inst();

        $config->remove('Page', 'constraints');
        $config->update('Page', 'constraints', array('Title' => array('MinLengthConstraint' => 'length=10')));
        
        $extended = new ConstraintsExtension();
        $extended->setOwner($page);
        $extended->injector = Injector::inst();
        
        $constraints = $extended->getConstraints();
        
        $this->assertEquals(1, count($constraints));
        
        $constraint = $constraints[0];
        
        $this->assertEquals('This page title', $constraint->getValue());
        
        $this->assertTrue($constraint->holds());
    }
    
    public function testValidationConstraints()
    {
        $config = Config::inst();
        $cur = $config->get('Page', 'extensions');
        $config->update('Page', 'extensions', array('SilverStripeAustralia\Constraints\Extensions\ConstraintsExtension'));
        
        $page = Page::create();
        $page->Title = 'Title';
        
        $config = Config::inst();

        $config->remove('Page', 'constraints');
        $config->update('Page', 'constraints', array('Title' => array('MinLengthConstraint' => 'length=10')));
        
        $valid = $page->validate();
        /* @var $valid ValidationResult */
        
        $this->assertFalse($valid->valid());
        
        $page->Title = 'Longer than 10 chars';
        
        $valid = $page->validate();
        
        $this->assertTrue($valid->valid());
        
        $config->update('Page', 'extensions', $cur);
    }
    
    
    public function testRegexConstraint()
    {
        $constraint = new SilverStripeAustralia\Constraints\Constraints\RegexConstraint("Some text");
        $constraint->setOption('regex', '^([a-z0-9]+)$');
        $constraint->setOption('modifiers', 'i');
        
        $this->assertFalse($constraint->holds());
        
        $constraint->setOption('regex', '^([a-z0-9 \@\(\)\*\/\:\.\,\&-]+)$');
        $this->assertTrue($constraint->holds());
        
        $constraint->setValue("Some text, with:all. char* () cont@ined & -others.");
        $this->assertTrue($constraint->holds());
        
        $constraint->setValue("Some text, with; other [ dodgy _ char } <>");
        $this->assertFalse($constraint->holds());
    }
}
