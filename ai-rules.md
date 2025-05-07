# Laravel Agent Coding Standards and Best Practices

This document provides simplified but professional rules for coding in Laravel, based on industry practices and Nuno Maduro's style.

## 1. General Principles

* **Single Responsibility:** Every class and method should do one thing.
* **Be Explicit:** Use type hints and clear method signatures.
* **Validate Early:** Use Form Requests to catch bad input early.
* **Follow Laravel Conventions:** Use standard structure and naming.
* **Readable Code:** Make code self-explanatory without needing comments.

## 2. Code Style

* Follow PSR-12 coding standards.
* Use 4 spaces for indentation.
* Keep line length under 120 characters.
* Always declare method and property visibility (`public`, `protected`, `private`).

## 3. Strict Formatting

* Add `declare(strict_types=1)` at the top of every file.
* Use `final` on classes/methods when possible.
* Avoid using `else` if you can return early.
* Keep class structure consistent: traits, constants, properties, constructor, methods.

## 4. Static Analysis and Quality Checks

* Use **Larastan** for static analysis.
* Use **PHP Insights** to monitor code quality.
* Run these tools on every pull request.

## 5. Actions

* Each action should handle one task only.
* Include `authorize()` and `rules()` in the action if needed.
* Use traits like `AsAction`, `AsController`, etc., to reuse logic.

## 6. Form Requests and Validation

* Use Form Request classes for all validation.
* Use DTOs to pass validated data into services.
* Create custom rules when built-in ones aren't enough.

## 7. Professional Patterns

* Use repositories to separate data access logic.
* Use service classes for complex business workflows.
* Use value objects and DTOs to organize data.
* Fire events to handle side effects (e.g., notifications).

## 8. Testing Best Practices

* Use Pest for clean, readable tests.
* Write feature tests for routes and APIs.
* Write unit tests for business logic.
* Use model factories and builders for test data.

## 9. Automation and Tooling

* Automate checks like Pint, Larastan, and Insights with Composer scripts.
* Use Git hooks to enforce code style before commits.
* Keep PHPDoc up to date for auto-generated documentation.

---

This file provides the key rules any Laravel developer or AI agent should follow to write clean, reliable, and professional code.
