---
applyTo: "tests/*Test.php"
---

- Each class:
    - extends a test case class directly or indirectly.
- Each test method:
    - names describes behavior being tested.
    - names use lowercase words and spaces replaced by an underscore (_); snake_case.
    - is identified using PHPUnit's `#[Test]` attribute, not naming convention.
    - is a member of one or more groups using PHPUnit's `#[Group('name')]` attribute.
